Yii-User Installation
=====================

Download
--------

Download or checkout (SVN/Git) from http://yii-user.2mx.org and unpack files in your protected/modules/user

Git clone
---------

    clone git git@github.com:mishamx/yii-user.git

Configure
---------

Change your config main:

    return array(
        #...
        // autoloading model and component classes
        'import'=>array(
            'application.models.*',
            'application.components.*',

            # Add the  module 'yii-user' here; you can also choose another location (like e.g. a common path)
            'application.modules.user.models.*',
            'application.modules.user.components.*',
        ),

        #...
        'modules'=>array(
            #...
            'user'=>array(
                # This path needs to be the path where you located the module 'yii-user'
                'class' => 'application.modules.user.UserModule',

                # Enter your application-wide layout path explictly here
                'layoutPath' => Yii::getPathOfAlias('application.views.layouts'),

                # encrypting method (php hash function)
                'hash' => 'md5',

                # send activation email
                'sendActivationMail' => true,

                # allow access for non-activated users
                'loginNotActiv' => false,

                # activate user on registration (only sendActivationMail = false)
                'activeAfterRegister' => false,

                # automatically login from registration
                'autoLogin' => true,

                # registration path
                'registrationUrl' => array('/user/registration'),

                # recovery password path
                'recoveryUrl' => array('/user/recovery'),

                # login form path
                'loginUrl' => array('/user/login'),

                # page after login
                'returnUrl' => array('/user/profile'),

                # page after logout
                'returnLogoutUrl' => array('/user/login'),
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
                # This path needs to be the path where you located the module 'yii-user'
                'class' => 'application.modules.user.UserModule',

                # encrypting method (php hash function)
                'hash' => 'md5',

                # send activation email
                'sendActivationMail' => true,

                # allow access for non-activated users
                'loginNotActiv' => false,

                # activate user on registration (only sendActivationMail = false)
                'activeAfterRegister' => false,

                # automatically login from registration
                'autoLogin' => true,

                # registration path
                'registrationUrl' => array('/user/registration'),

                # recovery password path
                'recoveryUrl' => array('/user/recovery'),

                # login form path
                'loginUrl' => array('/user/login'),

                # page after login
                'returnUrl' => array('/user/profile'),

                # page after logout
                'returnLogoutUrl' => array('/user/login'),
            ),
            #...
        ),
        #...
    );

Install
-------

Run command:
    yiic migrate --migrationPath=user.migrations

Input admin login, email and password
