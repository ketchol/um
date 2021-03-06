<?php
namespace IvanCLI\UM;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 19/02/2017
 * Time: 12:57 PM
 */
class MigrationCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'um:migration';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a migration following the UM specifications.';
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->laravel->view->addNamespace('um', substr(__DIR__, 0, -8).'views');
        $rolesTable          = Config::get('um.roles_table');
        $roleUserTable       = Config::get('um.role_user_table');
        $permissionsTable    = Config::get('um.permissions_table');
        $permissionRoleTable = Config::get('um.permission_role_table');
        $groupsTable         = Config::get('um.groups_table');
        $groupUserTable      = Config::get('um.group_user_table');
        $this->line('');
        $this->info( "Tables: $rolesTable, $roleUserTable, $permissionsTable, $permissionRoleTable, $groupsTable, $groupUserTable" );
        $message = "A migration that creates '$rolesTable', '$roleUserTable', '$permissionsTable', '$permissionRoleTable', '$groupsTable', '$groupUserTable'".
            " tables will be created in database/migrations directory";
        $this->comment($message);
        $this->line('');
        if ($this->confirm("Proceed with the migration creation? [Yes|no]", "Yes")) {
            $this->line('');
            $this->info("Creating migration...");
            if ($this->createMigration($rolesTable, $roleUserTable, $permissionsTable, $permissionRoleTable, $groupsTable, $groupUserTable)) {
                $this->info("Migration successfully created!");
            } else {
                $this->error(
                    "Couldn't create migration.\n Check the write permissions".
                    " within the database/migrations directory."
                );
            }
            $this->line('');
        }
    }

    /**
     * Create the migration.
     *
     * @param $rolesTable
     * @param $roleUserTable
     * @param $permissionsTable
     * @param $permissionRoleTable
     * @return bool
     *
     */
    protected function createMigration($rolesTable, $roleUserTable, $permissionsTable, $permissionRoleTable, $groupsTable, $groupUserTable)
    {
        $migrationFile = base_path("/database/migrations")."/".date('Y_m_d_His')."_um_setup_tables.php";
        $userModelName = Config::get('auth.providers.users.model');
        $userModel = new $userModelName();
        $usersTable = $userModel->getTable();
        $userKeyName = $userModel->getKeyName();
        $data = compact('rolesTable', 'roleUserTable', 'permissionsTable', 'permissionRoleTable', 'groupsTable', 'groupUserTable', 'usersTable', 'userKeyName');
        $output = $this->laravel->view->make('um::generators.migration')->with($data)->render();
        if (!file_exists($migrationFile) && $fs = fopen($migrationFile, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            return true;
        }
        return false;
    }
}