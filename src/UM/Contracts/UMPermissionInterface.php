<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 18/02/2017
 * Time: 10:49 PM
 */

namespace IvanCLI\UM\Contracts;


interface UMPermissionInterface
{
    /**
     * Many-to-Many relations with role model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles();
    
    /**
     * Many-to-One relations with permission model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentPerm();

    /**
     * One-to-Many relations with permission model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childPerms();
}