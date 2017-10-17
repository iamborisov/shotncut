<?php

namespace AppBundle\Admin;

class ContactsAdmin extends ContentAdmin {

    protected $group = 'contacts';
    protected $richEdit = false;
    protected $baseRouteName = 'contacts';
    protected $baseRoutePattern = 'contacts';

}