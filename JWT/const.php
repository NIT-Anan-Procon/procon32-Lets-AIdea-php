﻿<?php
/** JWT のアルゴリズム */
define('JWT_ALG', 'HS256');
/** JWT のキー */
define('JWT_KEY', '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890');
/** JWT の発行者 */
define('JWT_ISSUER', 'http://localhost');
/** JWT の有効期限 (秒) */
define('JWT_EXPIRES', 3600);