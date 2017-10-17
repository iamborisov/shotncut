<?php

namespace AppBundle\Admin;

class SeoAdmin extends ContentAdmin {

    protected $group = 'seo';
    protected $richEdit = false;
    protected $baseRouteName = 'seo';
    protected $baseRoutePattern = 'seo';

}