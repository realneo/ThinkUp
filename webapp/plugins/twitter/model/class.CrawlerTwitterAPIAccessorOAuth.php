<?php
/**
 *
 * ThinkUp/webapp/plugins/twitter/model/class.CrawlerTwitterAPIAccessorOAuth.php
 *
 * Copyright (c) 2009-2013 Gina Trapani
 *
 * LICENSE:
 *
 * This file is part of ThinkUp (http://thinkupapp.com).
 *
 * ThinkUp is free software: you can redistribute it and/or modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any
 * later version.
 *
 * ThinkUp is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with ThinkUp.  If not, see
 * <http://www.gnu.org/licenses/>.
 *
 *
 * Crawler TwitterAPI Accessor, via OAuth
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @copyright 2009-2013 Gina Trapani
 * @author Gina Trapani <ginatrapani[at]gmail[dot]com>
 *
 */
class CrawlerTwitterAPIAccessorOAuth extends TwitterAPIAccessorOAuth {
    /**
     *
     * @var int
     */
    var $archive_limit;
    /**
     *
     * @var int
     */
    var $num_retries = 2;
    /**
     * Constructor
     * @param str $oauth_token
     * @param str $oauth_token_secret
     * @param str $oauth_consumer_key
     * @param str $oauth_consumer_secret
     * @param Instance $instance
     * @param int $archive_limit
     * @param int $num_twitter_errors
     * @return CrawlerTwitterAPIAccessorOAuth
     */
    public function __construct($oauth_token, $oauth_token_secret, $oauth_consumer_key, $oauth_consumer_secret,
    $archive_limit, $num_twitter_errors) {
        parent::__construct($oauth_token, $oauth_token_secret, $oauth_consumer_key, $oauth_consumer_secret,
        $num_twitter_errors);
        $this->archive_limit = $archive_limit;
    }
    /**
     * Initalize the API accessor.
     */
    public function init() {
        $logger = Logger::getInstance();
        $status_message = "";
    }
    /**
     * Make Twitter API request.
     * @param str $url
     * @param array $args URL query string parameters
     * @param bool $suppress_404_error Defaults to false, don't log 404 errors from deleted tweets
     * @return array (cURL status, cURL content returned)
     */
    public function apiRequest($url, $args = array(), $suppress_404_error = false) {
        $logger = Logger::getInstance();
        $attempts = 0;
        $continue = true;

        // check for api function caller limits
        $caller_data = debug_backtrace();
        $calling_function = $caller_data[1]['function'];
        $calling_line = $caller_data[1]['line'];

        while ($attempts <= $this->num_retries && $continue) {
            $content = $this->to->OAuthRequest($url, 'GET', $args);
            $status = $this->to->lastStatusCode();

            $status_message = "";
            if ($status > 200) {
                $status_message = "Could not retrieve $url";
                if (sizeof($args) > 0) {
                    $status_message .= "?";
                }
                foreach ($args as $key=>$value) {
                    $status_message .= $key."=".$value."&";
                }
                $translated_status_code = $this->translateErrorCode($status);
                $status_message .= " | API ERROR: $translated_status_code";

                //we expect a 404 when checking a tweet deletion, so suppress log line if defined
                if ($status == 404) {
                    if ($suppress_404_error === false ) {
                        $logger->logUserError($status_message, __METHOD__.','.__LINE__);
                    }
                } else { //do log any other kind of error
                    $logger->logUserError($status_message, __METHOD__.','.__LINE__);
                }

                $status_message = "";
                if ($status != 404 && $status != 403) {
                    $attempts++;
                    if ($this->total_errors_so_far >= $this->total_errors_to_tolerate) {
                        $continue = false;
                    } else {
                        $this->total_errors_so_far = $this->total_errors_so_far + 1;
                        $logger->logUserInfo('Total API errors so far: ' . $this->total_errors_so_far .
                            ' | Total errors to tolerate '. $this->total_errors_to_tolerate, __METHOD__.','.__LINE__);
                    }
                } else {
                    $continue = false;
                }
            } else {
                $continue = false;
                $url = Utils::getURLWithParams($url, $args);
                $status_message = "API request: ".$url;
                $logger->logInfo($status_message, __METHOD__.','.__LINE__);
            }
        }
        return array($status, $content);
    }
}
