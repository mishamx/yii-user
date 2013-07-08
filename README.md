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

<table><tbody><tr><td>Property</td><td>Type</td><td>Description</td><td>Default</td></tr> <tr><td>user_page_size</td><td>int</td><td>items on page</td><td>10</td></tr> <tr><td>fields_page_size</td><td>int</td><td>items on page</td><td>10</td></tr> <tr><td>hash</td><td>string</td><td>hash method</td><td>md5</td></tr> <tr><td>sendActivationMail</td><td>boolean</td><td>use email for activation user account</td><td>true</td></tr> <tr><td>loginNotActiv</td><td>boolean</td><td>allow auth for is not active user</td><td>false</td></tr> <tr><td>activeAfterRegister</td><td>boolean</td><td>activate user on registration (only $sendActivationMail = false)</td><td>false</td></tr> <tr><td>autoLogin</td><td>boolean</td><td>login after registration (need loginNotActiv or activeAfterRegister = true)</td><td>true</td></tr> <tr><td>registrationUrl</td><td>array</td><td>regitration path</td><td>array("/user/registration")</td></tr> <tr><td>recoveryUrl</td><td>array</td><td>recovery path</td><td>array("/user/recovery/recovery")</td></tr> <tr><td>loginUrl</td><td>array</td><td>login path</td><td>array("/user/login")</td></tr> <tr><td>logoutUrl</td><td>array</td><td>logout path</td><td>array("/user/logout")</td></tr> <tr><td>profileUrl</td><td>array</td><td>profile path</td><td>array("/user/profile")</td></tr> <tr><td>returnUrl</td><td>array</td><td>return path after login</td><td>array("/user/profile")</td></tr> <tr><td>returnLogoutUrl</td><td>array</td><td>return path after logout</td><td>array("/user/login")</td></tr> <tr><td>relations</td><td>array</td><td>User model relation from other models</td><td>array()</td></tr> <tr><td>profileRelations</td><td>array</td><td>Profile model relation from other models</td><td>array()</td></tr> <tr><td>captcha</td><td>array</td><td>use captcha</td><td>array('registration'=&gt;true)</td></tr> <tr><td>tableUsers</td><td>string</td><td>User model table</td><td>{{users}}</td></tr> <tr><td>tableProfiles</td><td>string</td><td>Profile model table</td><td>{{profiles}}</td></tr> <tr><td>tableProfileFields</td><td>string</td><td>ProfileField model table</td><td>{{profiles_fields}}</td></tr> </tbody></table>
