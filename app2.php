<?php

require_once './vendor/autoload.php';
use Appwrite\Client;
use Appwrite\Services\Database;
use Appwrite\Services\Storage;
use Appwrite\Services\Users;



$ch = curl_init();
$certificate_location = "/usr/local/openssl-0.9.8/certs/cacert.pem";
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $certificate_location);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $certificate_location);

$ENDPOINT = 'https://localhost/v1';
$PROJECT_ID = '5f82e30055888';
$API_KEY = 'fe57886fa579badd40b576ae25cd704551b7a7649175010443b64d9d291db7ceba12180155f6bf7f8029cd2efbfe7b99368e1d96d788d28950fc6e6422373b34c3ddb5cb0bbe4a0bb9ed104ac307989bc6428a439dd071b0479b6b4e39aa68f0be1fc06b4ddb0ee42424ee25ae075229c4af7ff6abe9000f9e6d749990ccc8d0';

$client = new Client();

$client->setEndpoint($ENDPOINT);
$client->setProject($PROJECT_ID);
$client->setKey($API_KEY);
$collectionID = $userID = 0;
$database = new Database($client);
$storage = new Storage($client);
$users = new Users($client);

# API Calls
#   - api.create_collection
#   - api.list_collection
#   - api.add_doc
#   - api.list_doc
#   - api.upload_file
#   - api.list_files
#   - api.delete_file
#   - api.create_user
#   - api.list_user

# List of API definitions

/**
 * @throws Exception
 */
function create_collection()
{
    # code...to create collection
    global $collectionID, $database;

    $response = $database->createCollection('Movies',
        ['*'],
        ['*'],
        [
            [
                'label' => "Name",
                'key' => "name",
                'type' => "text",
                'default' => "Empty Name",
                'required' => true,
                'array' => false
            ],
            [
                'label' => 'release_year',
                'key' => 'release_year',
                'type' => 'numeric',
                'default' => 1970,
                'required' => true,
                'array' => false
            ]
        ]
    );

    $collectionID = $response['id'];

    var_dump($response);
}

/**
 * @throws Exception
 */
function list_collection()
{
    global $database;

    echo str_cli_color("Running List Collection API",'f_green, b_black');

    $response = $database->listCollections();
    
    $collection = $response['$collection'];

    var_dump($collection);
}

/**
 * @throws Exception
 */
function add_doc()
{
    global $collectionId;
    global $database;

    $collectionId = "0";

    echo str_cli_color("Running Add Document API",'f_green,b_black');

    $response = $database->createDocument(
        $collectionId,
        [
            'name' => "Spider Man",
            'release_year' => '1920',
        ],
        ['*'],
        ['*']
    );

    var_dump($response);
}

/**
 * @throws Exception
 */
function upload_files()
{
    global $storage;
    $filename = 'test.txt';

    echo str_cli_color("Running upload file API",'f_green,b_black');

    $response = $storage->createFile(
        curl_file_create($filename),
        [],
        []
    );

    var_dump($response);
}

/**
 * @throws Exception
 */
function list_files()
{
    global $storage;

    echo str_cli_color("Running List Files API",'f_green,b_black');

    $result = $storage->listFiles();

    $file_count = $result['sum'];
    $files = $result['files'];

    var_dump($file_count, $files);
}

/**
 * @throws Exception
 */
function delete_file()
{
    global $storage;
    

    echo str_cli_color("Running Delete File API",'f_green','b_black');

    $result = $storage->listFiles();
    $first_file_id = 'test.txt';
    //$first_file_id = $result['files'][0]['$id'];
    $response = $storage->deleteFile($first_file_id);

    var_dump($response);
}

/**
 * @param $email
 * @param $password
 * @param $name
 * @throws Exception
 */
function create_user($email, $password, $name)
{
    global $userId, $users;

    echo str_cli_color("Running create user API",'f_green,b_black');

    $response = $users->create(
        $email,
        $password,
        $name
    );
    $userId = $response['$id'];

    var_dump($response);
}

/**
 * @throws Exception
 */
function list_user()
{
    global $users;

    echo str_cli_color("Running list user api","f_green,b_black");

    $response = $users->list();

    var_dump($response);
}

/**
 * @throws Exception
 */
function run_all_tasks()
{
    
    $name = time();
    create_collection();
    list_collection();
    add_doc();
    //list_doc();
    upload_files();
    list_files();
    delete_file();
    create_user(
        $name . '@test.com',
        $name . '@123',
        $name
    );
    list_user();
}

try {
    run_all_tasks();
} catch (Exception $e) {
    die($e->getMessage());
}

echo str_cli_color("successfully run playground","f_green,b_black");
?>