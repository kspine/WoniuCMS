<?php

class UserAction extends CommonAction
{
    public function avatar()
    {
        if ($email) {
            $avatar = "http://www.gravatar.com/avatar/" . md5(strtolower(trim($email)));
        } else {
            $avatar = "http://www.gravatar.com/avatar/";
        }
        // return $avatar;
        $this->display();
    }

}
