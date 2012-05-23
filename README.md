Yii-User Installation
=====================

Download
--------

Download or checkout (SVN/Git) from http://yii-user.2mx.org and unpack files in your project/protected/modules/user

Configure
---------

Change your config main:

    return array(
        #...
        // autoloading model and component classes
        'import'=>array(
            'application.models.*',
            'application.components.*',
            'application.modules.user.models.*',
            'application.modules.user.components.*',
        ),
        #...
        'modules'=>array(
            #...
            'user'=>array(
                'hash' => 'md5',                                     # encrypting method (php hash function)
                'sendActivationMail' => true,                        # send activation email
                'loginNotActiv' => false,                            # allow access for non-activated users
                'activeAfterRegister' => false,                      # activate user on registration (only sendActivationMail = false)
                'autoLogin' => true,                                 # automatically login from registration
                'registrationUrl' => array('/user/registration'),    # registration path
                'recoveryUrl' => array('/user/recovery'),            # recovery password path
                'loginUrl' => array('/user/login'),                  # login form path
                'returnUrl' => array('/user/profile'),               # page after login
                'returnLogoutUrl' => array('/user/login'),           # page after logout
            ),
            #...
        ),
        #...
        // application components
        'components'=>array(
        #...
            'db'=>array(
            #...
                'tablePrefix' => 'tbl_',
            #...
            ),
            #...
            'user'=>array(
                // enable cookie-based authentication
                'class' => 'WebUser',
                'allowAutoLogin'=>true,
                'loginUrl' => array('/user/login'),
            ),
        #...
        ),
        #...
    );

Change your config console:

    return array(
        #...
        'modules'=>array(
            #...
            'user'=>array(
                'hash' => 'md5',                                     # encrypting method (php hash function)
                'sendActivationMail' => true,                        # send activation email
                'loginNotActiv' => false,                            # allow access for non-activated users
                'activeAfterRegister' => false,                      # activate user on registration (only sendActivationMail = false)
                'autoLogin' => true,                                 # automatically login from registration
                'registrationUrl' => array('/user/registration'),    # registration path
                'recoveryUrl' => array('/user/recovery'),            # recovery password path
                'loginUrl' => array('/user/login'),                  # login form path
                'returnUrl' => array('/user/profile'),               # page after login
                'returnLogoutUrl' => array('/user/login'),           # page after logout
            ),
            #...
        ),
        #...
    );

Install
-------

Run command: yiic migrate --migrationPath=user.migrations

Login
-----

Default users:

* admin/admin
* demo/demo