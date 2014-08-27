<?php
 /**
  * Class AccessAttempt
  *
  * filename:   AccessAttempt.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/22/14 8:46 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class AccessAttempt extends Eloquent
{
    protected $table        =   'access_attempts';
    protected $primaryKey   =   'id';
    protected $connection   =   'utils_db';
    protected $fillable     =   array
                                (
                                    'user_id',
                                    'attempt_type',
                                    'success',
                                    'attempted_at',
                                );
    protected $guarded      =   array
                                (
                                    'id',
                                );


    public function getAccessAttemptByUserIDs($accessFormName, $userIDArray, $timeFrame)
    {
        $Now		=	strtotime('now');

        switch($timeFrame)
        {
            case 'Last1Hour'                :   $startTime  =   $Now - (60 * 60);
                                                $endTime    =   $Now;
                                                break;

            case 'Last12Hours'              :   $startTime  =   0;
                                                $endTime    =   0;
                                                break;

            case 'Last24Hours'              :   $startTime  =   0;
                                                $endTime    =   0;
                                                break;

            case 'Today'                    :   $startTime  =   0;
                                                $endTime    =   0;
                                                break;

            case 'Last7Days'                :   $startTime  =   0;
                                                $endTime    =   0;
                                                break;

            case 'ThisWeekStartingMonday'   :   $startTime  =   0;
                                                $endTime    =   0;
                                                break;

            case 'ThisWeekStartingSunday'   :   $startTime  =   0;
                                                $endTime    =   0;
                                                break;

            case 'ThisMonth'                :   $startTime  =   0;
                                                $endTime    =   0;
                                                break;

            case 'ThisQuarter'              :   $startTime  =   0;
                                                $endTime    =   0;
                                                break;

            case 'ThisYear'                 :   $startTime  =   0;
                                                $endTime    =   0;
                                                break;


            default : throw new \Exception('An invalid time-frame was specified.');
        }

        $selectTotal        =   DB::connection($this->connection)
                                    ->table($this->table)
                                    ->select('id')
                                    ->where('attempt_type', '=', $accessFormName)
                                    ->whereIn('user_id', $userIDArray)
                                    ->where('attempted_at', '>=', $startTime)
                                    ->where('attempted_at', '<=', $endTime)
                                    ->get();
        $countTotal         =   count($selectTotal);


        $selectSuccess      =   DB::connection($this->connection)
                                    ->table($this->table)
                                    ->select('id')
                                    ->where('attempt_type', '=', $accessFormName)
                                    ->where('success', '=', 1)
                                    ->whereIn('user_id', $userIDArray)
                                    ->where('attempted_at', '>=', $startTime)
                                    ->where('attempted_at', '<=', $endTime)
                                    ->get();
        $countSuccess       =   count($selectSuccess);


        $selectFailures     =   DB::connection($this->connection)
                                    ->table($this->table)
                                    ->select('id')
                                    ->where('attempt_type', '=', $accessFormName)
                                    ->where('success', '=', 0)
                                    ->whereIn('user_id', $userIDArray)
                                    ->where('attempted_at', '>=', $startTime)
                                    ->where('attempted_at', '<=', $endTime)
                                    ->get();
        $countFailures      =   count($selectFailures);

        return  array
                (
                    'status'    =>  (isset($countTotal)    && is_int($countTotal)    && $countTotal    >= 0) &&
                     				(isset($countSuccess)  && is_int($countSuccess)  && $countSuccess  >= 0) &&
                   					(isset($countFailures) && is_int($countFailures) && $countFailures >= 0)
										?	TRUE
										:	FALSE,

                    'total'     =>  (isset($countTotal)    && is_int($countTotal)    && $countTotal    >= 0
										?	$countTotal
										:	FALSE),

                    'success'   =>  (isset($countSuccess)  && is_int($countSuccess)  && $countSuccess  >= 0
										?	$countSuccess
										:	FALSE),

                    'failures'  =>  (isset($countFailures) && is_int($countFailures) && $countFailures >= 0
										?	$countFailures
										:	FALSE),
                );
    }

    public function registerAccessAttempt($userID=0, $accessFormName, $attemptBoolean)
    {
        $newAccessAttempt   =   AccessAttempt::create
                                (
                                    array
                                    (
                                        'user_id'       =>  $userID,
                                        'attempt_type'  =>  $accessFormName,
                                        'success'       =>  $attemptBoolean,
                                        'attempted_at'  =>  strtotime('now'),
                                    )
                                );
        $newAccessAttempt->save();


        return TRUE;
    }
}