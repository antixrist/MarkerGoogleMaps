<?php
/**
 * StoreLocator
 *
 * Copyright 2011-12 by SCHERP Ontwikkeling <info@scherpontwikkeling.nl>
 *
 * This file is part of StoreLocator.
 *
 * StoreLocator is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * StoreLocator is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * StoreLocator; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package StoreLocator
 */

if (!$modx->user->isAuthenticated('mgr')) return $modx->error->failure($modx->lexicon('permission_denied'));

$where = array();

$parents = $modx->getOption('parents', $scriptProperties, '');
if($parents!=''){
	$where['parent:IN'] = explode(",",$parents);
}

$query = $modx->getOption('query', $scriptProperties, '');
if(!empty($query)){
	$where['pagetitle:LIKE'] = '%'.$query.'%';
}

$c = $modx->newQuery('modResource');
$c->sortby($modx->getOption('sortBy', $scriptProperties, 'pagetitle'),$modx->getOption('sortDir', $scriptProperties, 'ASC'));
if(!empty($where)){
	$c->where($where);
}
$resources = $modx->getCollection('modResource', $c);

$list = array();
if(!isset($_REQUEST['mode']) || $_REQUEST['mode']!='destpage'){
    $list[]=array('id'=>0,'pagetitle'=>'-');
}
foreach ($resources as $resource) {
    $list[] = $resource->toArray();
}

return $this->outputArray($list, sizeof($list));