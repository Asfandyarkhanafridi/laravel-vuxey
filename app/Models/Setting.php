<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Integer;
use function Symfony\Component\String\length;
use function Yajra\DataTables\paginate;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';
    protected $primaryKey = 'settingID';
    protected $guarded= [];
    public $timestamps = false;


    public function userSettings(){
        return $this->hasMany('App\Models\UserSetting', 'settingID', 'settingID');
    }

    public function users(){
        return $this->belongsToMany('App\Models\User', 'userSettings', 'settingID', 'userID');
    }

    public static function getSettingGroups() {
        $qrySettingGroups = "
            SELECT DISTINCT
                settingID,
				settingPanel,
				settingGroup,
				settingLabel,
				settingCode,
				sortOrder,
				fieldType,
				isShowDefaultValue,
				isShowUserEmailAsDefault,
				defaultValue
			FROM settings
			ORDER BY settingGroup
        ";
        return DB::select($qrySettingGroups);
    }

    public static function getUserSettings($userID){
        if ($userID>0){
            $currentUserID = $userID;
        }
        else{
            $currentUserID = Auth::id();
        }
        $qryGetUserSettings = "
           SELECT
				settings.settingID,
				settings.settingPanel,
				settings.settingGroup,
				settings.settingLabel,
				settings.settingCode,
				settings.sortOrder,
				settings.fieldType,
				settings.isShowDefaultValue,
				settings.isShowUserEmailAsDefault,
				settings.defaultValue,
				userSettings.settingValue,
				(SELECT email FROM users WHERE userID = $currentUserID LIMIT 0,1) AS userEmailAddress
			FROM
				settings
			LEFT JOIN userSettings ON userSettings.settingID = settings.settingID AND userSettings.userID = $currentUserID
			ORDER BY settings.settingPanel,settings.settingGroup,settings.sortOrder
        ";
        return DB::select($qryGetUserSettings);
    }

    public static function getUserSettingByID($settingID){
        $userID = Auth::id();
        $qryGetUserSettingByID  = "
           SELECT
				settingID,
				settingValue
			FROM
				userSettings
			WHERE
				settingID = $settingID
				AND
				userID = $userID
        ";
        return $qryGetUserSettingByID ;
    }

    public static function addUserSetting($settingID, String $settingValue){
        $userID = Auth::id();
        $qryAddUserSetting   = "
          INSERT INTO userSettings (
				userID,
				settingID,
				settingValue,
				createdByUserID
			) VALUES (
				$userID,
				$settingID,
				$settingValue,
				$userID
			)
        ";
        return $qryAddUserSetting  ;
    }

    public static function updateUserSetting($settingID, String $settingValue){
        $userID = Auth::id();
        $qryUpdateUserSetting   = "
             UPDATE userSettings SET
                    settingValue = $settingValue
                WHERE
                    settingID = $settingID
                    AND
                    userID = $userID
        ";
        return $qryUpdateUserSetting  ;
    }

    public static function deleteUserSetting($settingID){
        $userID = Auth::id();
        $qryDeleteUserSetting    = "
            DELETE FROM userSettings
                WHERE
                    settingID = $settingID
                    AND
                    userID = $userID
        ";
        return $qryDeleteUserSetting  ;
    }

    public static function getUserSettingStructure($userID){
        $userID = Auth::id();
        $qryGetUserSettingsStructure = [];
        $qryGetUserSettings = Setting::getUserSettings($userID);
        if (length(trim($qryGetUserSettings->settingValue))){
            $qryGetUserSettingsStructurep[$qryGetUserSettings->settingCode] = trim($qryGetUserSettings->settingValue);
        }
        else if(!(length(trim($qryGetUserSettings->defaultValue))) && ($qryGetUserSettings->isShowUserEmailAsDefault == 1) ){
            $qryGetUserSettingsStructure[$qryGetUserSettings->settingCode] = trim($qryGetUserSettings->userEmailAddress);
        }
        else{
            $qryGetUserSettingsStructure[$qryGetUserSettings->settingCode] = trim($qryGetUserSettings->defaultValue);
        }
        return $qryGetUserSettingsStructure;
    }

    public static function getAllUsersSettings(){
        $stUsersSettings = [];
        $qryUser = "SELECT userID FROM users ORDER BY userID";

        $stUsersSettings[$qryUser->userID] = Setting::getUserSettingsStructure($qryUser->userID);
        return $stUsersSettings;
    }

    public static function getMessageEvents(){
        $userID = Auth::id();
        $qryGetMessageEvents = "
              SELECT
                    eventID,
                    eventName,
                    dayInterval,
                    eventTypeID,
                    eventTime,
                    defaultTemplateID,
                    createdByUserID,
                    dateCreated
                FROM messageEvents
                WHERE
                    createdByUserID IN ($userID,0)
                ORDER BY createdByUserID,eventName
        ";
        return $qryGetMessageEvents;
    }
}
