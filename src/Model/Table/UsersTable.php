<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class UsersTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('users'); // our table
        $this->displayField('username'); 
        $this->primaryKey('id'); // Primary key 

        $this->addBehavior('Timestamp'); // Timestamp records on creation/modification
    }
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create'); // id field is assigned by the database
        $validator
            ->requirePresence('username', 'create') // check that the username is unique
            ->notEmpty('username')
            ->alphaNumeric('username')
            ->add('username', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);
        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password', 'You must enter a password', 'create')
                ->add('password', [
                    'length' => [
                    'rule' => ['minLength', 1],
                    'message' => 'Passwords must be at least 8 characters long.',
                ]
            ]);
        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name')
            ->alphaNumeric('name');

        $validator
            ->requirePresence('address', 'create')
            ->notEmpty('address')
            ->alphaNumeric('address');
 
        $validator
            ->dateTime('timeout')
            ->allowEmpty('timeout');
        return $validator;
    }
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['username']));
        return $rules;
    }
}