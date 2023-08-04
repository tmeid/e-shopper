<?php 
namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface{
    public function getModel(){
        return User::class;
    }
    public function getAddresses($user_id){
        return $this->model->find($user_id)->addresses;
        
    }
    public function getUsers(){
        return $this->model->withTrashed()->get();
    }
    public function getTrashedUser($id){
        return $this->model->onlyTrashed()->find($id);
    }

    public function userPaginate($request){

        // filter key hold data
        $search = null;
        $perPage = 10;

        if(!empty($request->search)){
            // validate $search later
            $search = $request->search;
            $search_pattern =  str_replace(' ', '%', $search);

            $usersQuery = $this->model->where(function($query) use ($search_pattern){
                $query->where('name', 'like', "%$search_pattern%");
                $query->orWhere('email', 'like', "%$search_pattern%");
                $query->orWhere('phone', 'like', "%$search_pattern%");

            });
        }else{
            $usersQuery = $this->model;
        }


        // sort by 
        $usersQuery->orderBy('created_at', 'DESC');
        
        return [
                'users' => $usersQuery->withTrashed()->paginate($perPage)->withQueryString(),
                'search' => $search
            ];
        
    }
}