<?php

    namespace common\models;

    use backend\modules\MadActiveRecord\models\MadActiveRecord;
    use common\commands\AddToTimelineCommand;
    use common\models\query\UserQuery;
    use Yii;
    use yii\behaviors\AttributeBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\helpers\ArrayHelper;
    use yii\web\IdentityInterface;

    /**
     * User model
     *
     * @property integer $id
     * @property string $username
     * @property string $password_hash
     * @property string $email
     * @property string $auth_key
     * @property string $access_token
     * @property string $oauth_client
     * @property string $oauth_client_user_id
     * @property string $publicIdentity
     * @property integer $status
     * @property integer $created_at
     * @property integer $updated_at
     * @property integer $logged_at
     * @property string $password write-only password
     * @property string $isHotel [varchar(3)]
     *
     * @property \common\models\UserProfile $userProfile
     */
    class User extends MadActiveRecord implements IdentityInterface {

        const STATUS_NOT_ACTIVE = 1;
        const STATUS_ACTIVE = 2;
        const STATUS_DELETED = 3;

        const ROLE_ADMINISTRATOR = 'administrator';
        const ROLE_USER = 'user';

        const ROLE_OFFICE_ADMIN = 'officeAdmin';
        const ASSIGN_OFFICE_ADMIN = 'assign_officeAdmin';

        const ROLE_TICKET_EDITOR = 'ticketEditor';
        const ASSIGN_TICKET_EDITOR = 'assign_ticketEditor';

        const ROLE_HOTEL_EDITOR = 'hotelEditor';
        const ASSIGN_HOTEL_EDITOR = 'assign_hotelEditor';

        const ROLE_HOTEL_SELLER = 'hotelSeller';
        const ASSIGN_HOTEL_SELLER = 'assign_hotelSeller';

        const ROLE_OFFICE_VISITOR = 'officeVisitor';
        const ASSIGN_OFFICE_VISITOR = 'assign_officeVisitor';

        const ROLE_STREET_ADMIN = 'streetAdmin';
        const ASSIGN_STREET_ADMIN = 'assign_streetAdmin';

        const ROLE_STREET_SELLER = 'streetSeller';
        const ASSIGN_STREET_SELLER = 'assign_streetSeller';

        const ROLE_HOTLINE = 'hotline';
        const ASSIGN_HOTLINE = 'assign_hotline';

        const EVENT_AFTER_SIGNUP = 'afterSignup';
        const EVENT_AFTER_LOGIN = 'afterLogin';

        /**
         *
         * @inheritdoc
         */
        public static function tableName() {
            return '{{%user}}';
        }

        /**
         *
         * @inheritdoc
         */
        public static function findIdentity($id) {
            return static::find()->active()
                ->andWhere([
                    'id' => $id
                ])
                ->one();
        }

        /**
         *
         * @return UserQuery
         */
        public static function find() {
            return new UserQuery(get_called_class());
        }

        /**
         *
         * @inheritdoc
         */
        public static function findIdentityByAccessToken($token, $type = null) {
            return static::find()->active()
                ->andWhere([
                    'access_token' => $token
                ])
                ->one();
        }

        /**
         * Finds user by username
         *
         * @param string $username
         *
         * @return User|array|null
         */
        public static function findByUsername($username) {
            return static::find()->active()
                ->andWhere([
                    'username' => $username
                ])
                ->one();
        }

        /**
         * Finds user by username or email
         *
         * @param string $login
         *
         * @return User|array|null
         */
        public static function findByLogin($login) {
            return static::find()->active()
                ->andWhere([
                    'or',
                    [
                        'username' => $login
                    ],
                    [
                        'email' => $login
                    ]
                ])
                ->one();
        }

        public static function getStreetSellers(){
            $userModel = new \common\models\User();
            $allUsers = $userModel->find()->all();
            $streetSellers = [];


            foreach ($allUsers as $user) {
                $authManager = \Yii::$app->getAuthManager();
                $hasStreetBool = $authManager->getAssignment('streetSeller', $user->id) ? true : false;

                if ($hasStreetBool) {
                    $streetSellers[] = $user;

                };

            }
            return $streetSellers;
        }
        public static function getHotelSellers(){
            $userModel = new \common\models\User();
            $allUsers = $userModel->find()->all();
            $hotelSellers = [];

            foreach ($allUsers as $user) {
                $authManager = \Yii::$app->getAuthManager();
                $hasHotelBool = $authManager->getAssignment('hotelSeller', $user->id) ? true : false;

                if ($hasHotelBool) {
                    $hotelSellers[]  = $user;

                };
            }
            return $hotelSellers;

        }

        /**
         *
         * @inheritdoc
         */
        public function behaviors() {
            return [
                TimestampBehavior::class,
                'auth_key' => [
                    'class' => AttributeBehavior::class,
                    'attributes' => [
                        MadActiveRecord::EVENT_BEFORE_INSERT => 'auth_key'
                    ],
                    'value' => function () {
                        return Yii::$app->getSecurity()->generateRandomString();
                    }
                ],
                'access_token' => [
                    'class' => AttributeBehavior::class,
                    'attributes' => [
                        MadActiveRecord::EVENT_BEFORE_INSERT => 'access_token'
                    ],
                    'value' => function () {
                        return Yii::$app->getSecurity()->generateRandomString(40);
                    }
                ]
            ];
        }

        /**
         *
         * @return array
         */
        public function scenarios() {
            return ArrayHelper::merge(parent::scenarios(),
                [
                    'oauth_create' => [
                        'oauth_client',
                        'oauth_client_user_id',
                        'email',
                        'username',
                        '!status'
                    ]
                ]);
        }

        /**
         *
         * @inheritdoc
         */
        public function rules() {
            return [
                [
                    [
                        'username',
                        'email'
                    ],
                    'unique'
                ],
                [
                    'status',
                    'default',
                    'value' => self::STATUS_NOT_ACTIVE
                ],
                [
                    'status',
                    'in',
                    'range' => array_keys(self::statuses())
                ],
                [
                    [
                        'username'
                    ],
                    'filter',
                    'filter' => '\yii\helpers\Html::encode'
                ]
            ];
        }

        /**
         * Returns user statuses list
         *
         * @return array|mixed
         */
        public static function statuses() {
            return [
                self::STATUS_NOT_ACTIVE => Yii::t('common', 'Not Active'),
                self::STATUS_ACTIVE => Yii::t('common', 'Active'),
                self::STATUS_DELETED => Yii::t('common', 'Deleted')
            ];
        }

        /**
         *
         * @inheritdoc
         */
        public function attributeLabels() {
            return [
                'username' => Yii::t('common', 'Username'),
                'email' => Yii::t('common', 'E-mail'),
                'status' => Yii::t('common', 'Status'),
                'access_token' => Yii::t('common', 'API access token'),
                'created_at' => Yii::t('common', 'Created at'),
                'updated_at' => Yii::t('common', 'Updated at'),
                'logged_at' => Yii::t('common', 'Last login'),
            ];
        }

        /**
         *
         * @return \yii\db\ActiveQuery
         */
        public function getUserProfile() {
            return $this->hasOne(UserProfile::class, [
                'user_id' => 'id'
            ]);
        }

        /**
         *
         * @inheritdoc
         */
        public function validateAuthKey($authKey) {
            return $this->getAuthKey() === $authKey;
        }

        /**
         *
         * @inheritdoc
         */
        public function getAuthKey() {
            return $this->auth_key;
        }

        /**
         * Validates password
         *
         * @param string $password
         *            password to validate
         *
         * @return boolean if password provided is valid for current user
         */
        public function validatePassword($password) {
            return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
        }

        /**
         * Generates password hash from password and sets it to the model
         *
         * @param string $password
         */
        public function setPassword($password) {
            $this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
        }

        /**
         * Creates user profile and application event
         *
         * @param array $profileData
         */
        public function afterSignup(array $profileData = []) {
            $this->refresh();
            Yii::$app->commandBus->handle(
                new AddToTimelineCommand(
                    [
                        'category' => 'user',
                        'event' => 'signup',
                        'data' => [
                            'public_identity' => $this->getPublicIdentity(),
                            'user_id' => $this->getId(),
                            'created_at' => $this->created_at
                        ]
                    ]));
            $profile = new UserProfile();
            $profile->locale = Yii::$app->language;
            $profile->load($profileData, '');
            $this->link('userProfile', $profile);
            $this->trigger(self::EVENT_AFTER_SIGNUP);
            // Default role
            $auth = Yii::$app->authManager;
            $auth->assign($auth->getRole(User::ROLE_USER), $this->getId());
        }

        /**
         *
         * @return string
         */
        public function getPublicIdentity() {
            if ($this->userProfile && $this->userProfile->getFullname()) {
                return $this->userProfile->getFullname();
            }
            if ($this->username) {
                return $this->username;
            }
            return $this->email;
        }

        /**
         *
         * @inheritdoc
         */
        public function getId() {
            return $this->getPrimaryKey();
        }

        /**
         * @param $role
         *
         * @return bool
         */
        public function hasRole($role) {
            $authManager = \Yii::$app->getAuthManager();
            $hasRole = $authManager->getAssignment($role, $this->id) ? true : false;
            return $hasRole;
        }
    }
