<?php
namespace App\Controllers\Admin;

use App\Models\Admin;
use App\Models\Permission;

use Library\Json;

use stdClass;

class Search
{
    private $data;
    private $paths;

    public function __construct(){
        $this->data = new stdClass();
    }

    public function playerid()
    {
        $string =  input()->get('searchTerm')->value ?? null;

        if(!isset($string)) {
            echo Json::raw(array(['id' => "none", 'text' => 'Where are you searching for?']));
            exit;
        }

        $userObject = Admin::getPlayersByString($string);
        foreach($userObject as $user) {
            $this->paths[] = array('id' => $user->id, 'text' => $user->username);
        }

        echo Json::raw($this->paths);
    }

    public function catalogueitem()
    {
        $string =  input()->get('searchTerm')->value ?? null;

        if(!isset($string)) {
            echo json_encode(array(['id' => "none", 'text' => 'Choose an catalogue page']));
            exit;
        }

        $userObject = Admin::getCataloguePage($string);
        foreach($userObject as $user) {
            $this->paths[] = array('id' => $user->id, 'text' => $user->caption);
        }

        echo json_encode($this->paths);
    }
      
    public function playername()
    {
        $string =  input()->get('searchTerm')->value ?? null;

        if(!isset($string)) {
            echo json_encode(array(['id' => "none", 'text' => 'Where are you searching for?']));
            exit;
        }

        $userObject = Admin::getPlayersByString($string);
        foreach($userObject as $user) {
            $this->paths[] = array('id' => $user->username, 'text' => $user->username);
        }

        echo json_encode($this->paths);
    }

    public function rooms()
    {
        $string = input()->get('searchTerm')->value ?? null;

        if(!isset($string)) {
            echo Json::raw(array(['id' => "none", 'text' => 'Where are you searching for?']));
            exit;
        }

        $roomObject = Admin::getRoomsByString($string);
        foreach($roomObject as $room) {
            $this->paths[] = array('id' => $room->id, 'text' => $room->name.' From: '.$room->owner.' Visitors: '.$room->users_now);
        }

        echo Json::raw($this->paths);
    }

    public function role()
    {
        $string = input()->get('searchTerm')->value ?? null;
        $roleObject = Permission::getRoles($string);
        foreach($roleObject as $rank) {
            $this->paths[] = array('id' => $rank->id, 'text' => $rank->rank_name);
        }

        echo Json::raw($this->paths);
    }

    public function permission()
    {
        $permission_id =  input()->get('searchTerm')->value ?? null;
        $role_id =  input()->get('roleid')->value;

        if(!isset($permission_id) && !isset($role_id)) {
            echo Json::raw(array(['id' => "none", 'text' => 'Where are you searching for?']));
            exit;
        }

        $rankObject = Permission::getPermissions($permission_id);
        foreach($rankObject as $rank) {
            if(!Permission::permissionExists($role_id, $rank->id)) {
                $this->paths[] = array('id' => $rank->id, 'text' => $rank->permission);
            }
        }

        if(empty($this->paths)) {
            echo Json::raw(array(['id' => "none", 'text' => 'This role has all the permissions they can have']));
            exit;
        }

        echo Json::raw($this->paths);
    }

    public function wordfilter()
    {
        $string = input()->get('searchTerm')->value ?? null;

        if(!isset($string)) {
            echo Json::raw(array(['id' => "none", 'text' => 'Where are you searching for?']));
            exit;
        }

        $wordObject = Admin::getWordsByString($string);
        foreach ($wordObject as $user) {
            $this->paths[] = array('id' => $user->key, 'text' => $user->key);
        }

        echo Json::raw($this->paths);
    }

    public function banfields()
    {
        $this->data->alertmessages  = Admin::getAlertMessages();
        $this->data->banmessages    = Admin::getBanMessages();
        $this->data->bantime        = Admin::getBanTime(request()->player->rank);

        if(!empty($this->data)) {
            Json::raw($this->data);
        }
    }
}
