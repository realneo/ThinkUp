<?php
/**
 *
 * ThinkUp/webapp/plugins/twitter/tests/TestOfCrawlerTwitterAPIAccessorOAuth.php
 *
 * Copyright (c) 2011-2013 Mark Wilkie
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
 * @author Mark Wilkie <mwilkie[at]gmail[dot]com>
 * @license http://www.gnu.org/licenses/gpl.html
 * @copyright 2011-2013 Mark Wilkie
 */
require_once dirname(__FILE__) . '/../../../../tests/init.tests.php';
require_once THINKUP_WEBAPP_PATH.'_lib/extlib/simpletest/autorun.php';
require_once THINKUP_ROOT_PATH.'tests/classes/class.ThinkUpBasicUnitTestCase.php';
require_once THINKUP_WEBAPP_PATH.'config.inc.php';
require_once THINKUP_WEBAPP_PATH.'plugins/twitter/tests/classes/mock.TwitterOAuth.php';
require_once THINKUP_WEBAPP_PATH.'plugins/twitter/model/class.TwitterAPIAccessorOAuth.php';
require_once THINKUP_WEBAPP_PATH.'plugins/twitter/model/class.CrawlerTwitterAPIAccessorOAuth.php';
require_once THINKUP_WEBAPP_PATH.'plugins/twitter/model/class.TwitterOAuthThinkUp.php';

class TestOfCrawlerTwitterAPIAccessorOAuth extends ThinkUpBasicUnitTestCase {
    public function setUp() {
    }

    public function tearDown() {
    }
}
