<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


# 接口域名
defined("DOMAIN")              OR define('DOMAIN', "http://sct.cdth.cn/");
defined("MY_DOMAIN")           OR define('MY_DOMAIN', "http://127.0.0.1/PHP-Sct/");
defined("APP_DOMAIN")           OR define('APP_DOMAIN', "http://sct.cdth.cn/");

defined('APP_DEFAULT_SIZE')    OR define('APP_DEFAULT_SIZE', 1048576);
# 后台分页默认大小
defined('ADMIN_PAGE_SIZE')    OR define('ADMIN_PAGE_SIZE', 10);
# PC分页默认大小
defined('PC_PAGE_SIZE')    OR define('PC_PAGE_SIZE', 12);

# 网站名字
defined('APP_SMS_NAME')					  OR define('APP_SMS_NAME', '顺驰通'); #APP短信名字

# 创新信息短信
defined('SMS_ID')                         OR define('SMS_ID', 1167); // 创新信息短信服务商ID
defined('SMS_ACCOUNT')                    OR define('SMS_ACCOUNT', '18781176753'); // 短信帐号
defined('SMS_PWD')                        OR define('SMS_PWD', '5280201'); // 短信密码
defined('EX_TIME')						  OR define('EX_TIME', 60); //短信过期时间


# 顺驰通微信
defined('WX_APPID')						  OR define('WX_APPID', 'wx848ae3f6d07751f5'); //微信APPID
defined('WX_SECRET')					  OR define('WX_SECRET', 'eb352beaf584a376fc1b824daeedbeb8'); //微信app_secret

# 支付宝测试账号
defined('AIL_PAY_ID')                     OR define('AIL_PAY_ID', '2088711652400440');//APP_ID
defined('AIL_MD5_KEY')                    OR define('AIL_MD5_KEY', 'l12y1maxca6io5agqyv1v0t4qteaa9fb');//MD5 key
defined('AIL_PUBLIC_KEY')                 OR define('AIL_PUBLIC_KEY', 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnxj/9qwVfgoUh/y2W89L6BkRAFljhNhgPdyPuBV64bfQNN1PjbCzkIM6qRdKBoLPXmKKMiFYnkd6rAoprih3/PrQEB/VsW8OoM8fxn67UDYuyBTqA23MML9q1+ilIZwBC2AQ2UBVOrFXfFl75p6/B5KsiNG9zpgmLCUYuLkxpLQIDAQAB');//支付宝公钥
defined('SELLER_PUBLIC_KEY')              OR define('SELLER_PUBLIC_KEY', 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCogCeEmz0la9PcpOMqNZ1VobvyU3/c12NsL1pVInsmx6DA0Yi4e1bv6uyylMAO/xzGj4cOXjGc4G2IRBn99mtXPSiqUZVLnK4hJL6lfzpuvqmQcD5lesIMOzYiWtFbZ3nn/GxKk8KF/AmyxKAbAL1C08ay9teqijRJ8XciAs5MfwIDAQAB');
defined('AIL_PRIVATE_KEY')                OR define('AIL_PRIVATE_KEY', 'MIICXAIBAAKBgQCogCeEmz0la9PcpOMqNZ1VobvyU3/c12NsL1pVInsmx6DA0Yi4e1bv6uyylMAO/xzGj4cOXjGc4G2IRBn99mtXPSiqUZVLnK4hJL6lfzpuvqmQcD5lesIMOzYiWtFbZ3nn/GxKk8KF/AmyxKAbAL1C08ay9teqijRJ8XciAs5MfwIDAQABAoGAVb7RXVO6K/7REyj9SI97/wWMpOYE3RbmSzlVmJkxXiycC0MVdfud4/0CcmXrzjXYKNsE+TTJvnEejAdLysbJHAplCm79EOoiNdaJ6POjtR6o04rOyP4QyityIfo000X8ReL3gUcoqjJZOrCuPrzeJJOhG/X0OZ+VuA2/GQQDp7ECQQDS+tbemy+E97XkkyDxjB4VdnIqLZC04f0C8HOXce5oKmyKsWustkMJQqkUAUE/uV/RZVbANyT+AabxXVjgigdFAkEAzHTPCvQXpYdZVCkgccPs4iRRK2GCVvywkihhJ5I7CNz0cRb/hWhVl/kQJ2jcZsCKVgWt5RrST4u6obidbr8u8wJBAMlOVh7o65pv0LpcOB7BlyLbdWsRNvWge42GaISkTNpPQGnFh/uvnJ8FX9aaq+tlsStXCkM1WrKSWPwMGXWFvhECQE+j0GI74lof9rPJsVGfN85+xv9W5CZuF3lXMUDwvP4e0ziZ9L5KfczMv3Yaan+70Cbh33K2l+VUUEZeWzSgU/ECQEWZ7qZAIOea2J0CYLlJmKjMqC5299HBxt8HNKj3CXqg8u/onQ77og+ViyM70L5gBCrKQoBy/Yt9pLsaMNHbzOE=');

# 微信帐号
defined('WX_APP_ID')                      OR define('WX_APP_ID', 'wxdcfea164e171dc90'); // 微信appid
defined('WX_MCH_ID')                      OR define('WX_MCH_ID', '1262914601'); // 微信商户号
defined('WX_KEY')                         OR define('WX_KEY', 'c56430cd1ea01885234b75d7a2ee00a1'); // 商户KEY
defined('WX_CALLBACK_URL')                OR define('WX_CALLBACK_URL', DOMAIN."Wallet/wx_callback");

# 功能限制
defined('CASH_MODE')					  OR define('CASH_MODE', 0); //测试模式 0  正常模式 1


# qq互联账号
defined('QQ_APP_ID')                      OR define('QQ_APP_ID', '101388636'); //QQ应用APP_ID
defined('QQ_APP_KEY')					  OR define('QQ_APP_KEY', '1ea1a0df6a8180604b7b17cf771be3df'); //QQ应用APP_KEY