<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

//this is custom table to join the existing table in 
//the database without creating it using the default method
class CustomModel
{

    protected $db;

    public function __construct(ConnectionInterface &$db)
    {
        $this->db = &$db;
    }

    function all()
    {
        //  Normal query
        // "SELECT * FROM posts";
        return $this->db->table('posts')->get()->getResult();
    }

    function where()
    {
        return $this->db->table('posts')
            ->where(['post_id >' => 50])
            ->where(['post_id <' => 55])
            ->where(['post_id !=' => 51])
            ->orderBy('post_id', 'DESC')
            ->get()
            ->getResult();
    }

    function join()
    {
        return $this->db->table('posts')
            ->where('post_id >', 50)
            ->where('post_id <', 60)
            //-----KALAU NK PKAI JOIN TYPE
            //->join('users','posts.post_author = users.user_id','left/right/inner/full  --- default akan amik inner join')
            ->join('users', 'posts.post_author = users.user_id')
            ->get()
            ->getResult();
    }

    function like()
    {
        return $this->db->table('posts')
            ->like('post_content', 'new', 'both') //3rd param specified string kat mana eg : %string%, %string, string% (default:both-->can be before,after,both)
            ->join('users', 'posts.post_author = users.user_id')
            ->get()
            ->getResult();
    }

    function grouping()
    {
        return $this->db->table('posts')
            ->groupStart()
            ->where(['post_id >'=> 25, 'post_created_at <' => '1990-01-01 00:00:00'])
            ->groupEnd()
            ->orWhere('post_author', 10)
            ->join('users', 'posts.post_author = users.user_id')
            ->get()
            ->getResult();
    }

    function wherein()
    {
        $emails = ["ressie59@example.org","chandler.abbott@example.com","jtorp@example.net"];
        return $this->db->table('posts')
            ->groupStart()
            ->where(['post_id >'=> 25, 'post_created_at <' => '1990-01-01 00:00:00'])
            ->groupEnd()
            ->orWhereIn('email', $emails)
            ->join('users', 'posts.post_author = users.user_id')
            ->limit(5)
            ->get()
            ->getResult();
    }

    function getPosts()
    {
        $builder = $this->db->table('posts');
        $builder->join('users', 'posts.post_author = users.user_id');
        $posts = $builder->get()->getResult();
        return $posts;
    }
}