<?php

class PageAction extends PublicAction
{
    function index($id = 0)
    {
        $p = M('articleGroup');


        $this->display();

    }
}