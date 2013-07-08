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
            'application.modules.user.models.*',
            'application.modules.user.components.*',
        ),

        #...
        'modules'=>array(
            #...
            'user'=>array(
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

                # User model table
                'tableUsers' => array('user'),
                    
                # Profile model table
                'tableProfiles' => array('user_profile'),
                    
                # ProfileField model table
                'tableProfileFields' => array('user_profile_field'),

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

                # User model table
                'tableUsers' => 'user',
                    
                # Profile model table
                'tableProfiles' => 'user_profile',
                    
                # ProfileField model table
                'tableProfileFields' => 'user_profile_field',

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

Module parameters
-----------------

<table><tbody><tr><td style="border: 1px solid #ccc; padding: 5px;">Property</td><td style="border: 1px solid #ccc; padding: 5px;">Type</td><td style="border: 1px solid #ccc; padding: 5px;">Description</td><td style="border: 1px solid #ccc; padding: 5px;">Default</td></tr> <tr><td style="border: 1px solid #ccc; padding: 5px;">user_page_size</td><td style="border: 1px solid #ccc; padding: 5px;">int</td><td style="border: 1px solid #ccc; padding: 5px;">items on page</td><td style="border: 1px solid #ccc; padding: 5px;">10</td></tr> <tr><td style="border: 1px solid #ccc; padding: 5px;">fields_page_sizeint</td><td style="border: 1px solid #ccc; padding: 5px;">items on page</td><td style="border: 1px solid #ccc; padding: 5px;">10</td></tr> <tr><td style="border: 1px solid #ccc; padding: 5px;">hash</td><td style="border: 1px solid #ccc; padding: 5px;">string</td><td style="border: 1px solid #ccc; padding: 5px;">hash method</td><td style="border: 1px solid #ccc; padding: 5px;">md5</td></tr> <tr><td style="border: 1px solid #ccc; padding: 5px;">sendActivationMail</td><td style="border: 1px solid #ccc; padding: 5px;">boolean</td><td style="border: 1px solid #ccc; padding: 5px;">use email for activation user account</td><td style="border: 1px solid #ccc; padding: 5px;">true</td></tr> <tr><td style="border: 1px solid #ccc; padding: 5px;">loginNotActiv</td><td style="border: 1px solid #ccc; padding: 5px;">boolean</td><td style="border: 1px solid #ccc; padding: 5px;">allow auth for is not active user</td><td style="border: 1px solid #ccc; padding: 5px;">false</td></tr> <tr><td style="border: 1px solid #ccc; padding: 5px;">activeAfterRegister</td><td style="border: 1px solid #ccc; padding: 5px;">boolean</td><td style="border: 1px solid #ccc; padding: 5px;">activate user on registration (only $sendActivationMail = false)</td><td style="border: 1px solid #ccc; padding: 5px;">false</td></tr> <tr><td style="border: 1px solid #ccc; padding: 5px;">autoLogin</td><td style="border: 1px solid #ccc; padding: 5px;">boolean</td><td style="border: 1px solid #ccc; padding: 5px;">login after registration (need loginNotActiv or activeAfterRegister = true)</td><td style="border: 1px solid #ccc; padding: 5px;">true</td></tr> <tr><td style="border: 1px solid #ccc; padding: 5px;">registrationUrl</td><td style="border: 1px solid #ccc; padding: 5px;">array</td><td style="border: 1px solid #ccc; padding: 5px;">regitration path</td><td style="border: 1px solid #ccc; padding: 5px;">array("/user/registration")</td></tr> <tr><td style="border: 1px solid #ccc; padding: 5px;">recoveryUrl</td><td style="border: 1px solid #ccc; padding: 5px;">array</td><td style="border: 1px solid #ccc; padding: 5px;">recovery path</td><td style="border: 1px solid #ccc; padding: 5px;">array("/user/recovery/recovery")</td></tr> <tr><td style="border: 1px solid #ccc; padding: 5px;">loginUrl</td><td style="border: 1px solid #ccc; padding: 5px;">array</td><td style="border: 1px solid #ccc; padding: 5px;">login path</td><td style="border: 1px solid #ccc; padding: 5px;">array("/user/login")</td></tr> <tr><td style="border: 1px solid #ccc; padding: 5px;">logoutUrl</td><td style="border: 1px solid #ccc; padding: 5px;">array</td><td style="border: 1px solid #ccc; padding: 5px;">logout path</td><td style="border: 1px solid #ccc; padding: 5px;">array("/user/logout")</td></tr> <tr><td style="border: 1px solid #ccc; padding: 5px;">profileUrl</td><td style="border: 1px solid #ccc; padding: 5px;">array</td><td style="border: 1px solid #ccc; padding: 5px;">profile path</td><td style="border: 1px solid #ccc; padding: 5px;">array("/user/profile")</td></tr> <tr><td style="border: 1px solid #ccc; padding: 5px;">returnUrl</td><td style="border: 1px solid #ccc; padding: 5px;">array</td><td style="border: 1px solid #ccc; padding: 5px;">return path after login</td><td style="border: 1px solid #ccc; padding: 5px;">array("/user/profile")</td></tr> <tr><td style="border: 1px solid #ccc; padding: 5px;">returnLogoutUrl</td><td style="border: 1px solid #ccc; padding: 5px;">array</td><td style="border: 1px solid #ccc; padding: 5px;">return path after logout</td><td style="border: 1px solid #ccc; padding: 5px;">array("/user/login")</td></tr> <tr><td style="border: 1px solid #ccc; padding: 5px;">relations</td><td style="border: 1px solid #ccc; padding: 5px;">array</td><td style="border: 1px solid #ccc; padding: 5px;">User model relation from other models</td><td style="border: 1px solid #ccc; padding: 5px;">array()</td></tr> <tr><td style="border: 1px solid #ccc; padding: 5px;">profileRelations</td><td style="border: 1px solid #ccc; padding: 5px;">array</td><td style="border: 1px solid #ccc; padding: 5px;">Profile model relation from other models</td><td style="border: 1px solid #ccc; padding: 5px;">array()</td></tr> <tr><td style="border: 1px solid #ccc; padding: 5px;">captcha</td><td style="border: 1px solid #ccc; padding: 5px;">array</td><td style="border: 1px solid #ccc; padding: 5px;">use captcha</td><td style="border: 1px solid #ccc; padding: 5px;">array('registration'=&gt;true)</td></tr> <tr><td style="border: 1px solid #ccc; padding: 5px;">tableUsers</td><td style="border: 1px solid #ccc; padding: 5px;">string</td><td style="border: 1px solid #ccc; padding: 5px;">User model table</td><td style="border: 1px solid #ccc; padding: 5px;">{{users}}</td></tr> <tr><td style="border: 1px solid #ccc; padding: 5px;">tableProfiles</td><td style="border: 1px solid #ccc; padding: 5px;">string</td><td style="border: 1px solid #ccc; padding: 5px;">Profile model table</td><td style="border: 1px solid #ccc; padding: 5px;">{{profiles}}</td></tr> <tr><td style="border: 1px solid #ccc; padding: 5px;">tableProfileFields</td><td style="border: 1px solid #ccc; padding: 5px;">string</td><td style="border: 1px solid #ccc; padding: 5px;">ProfileField model table</td><td style="border: 1px solid #ccc; padding: 5px;">{{profiles_fields}}</td></tr> </tbody></table>
