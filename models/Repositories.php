<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "repositories".
 *
 * @property int $id
 * @property string|null $html_url
 * @property string|null $name
 * @property string|null $updated_at
 * @property string|null $full_name
 * @property string|null $owner
 * @property string|null $description
 * @property string|null $id_rep
 */
class Repositories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'repositories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'updated_at', 'full_name', 'description', 'id_rep'], 'string', 'max' => 450],

            ['name', 'unique'],
//            ['name', 'skipOnEmpty' => true],

            [['owner',' html_url'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'html_url' => 'Html Url',
            'name' => 'Name',
            'updated_at' => 'Updated At',
            'full_name' => 'Full Name',
            'owner' => 'Owner',
            'description' => 'Description',
            'id_rep' => 'Id Rep',
        ];
    }
}
