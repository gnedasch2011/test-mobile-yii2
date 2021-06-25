<?php

namespace app\models;

use Yii;
use yii\base\Model;
use function app\controllers\getAllRepo;
use function app\controllers\getDataUser;
use function app\controllers\getLastUpdatedRepo;

/**
 * This is the model class for table "repositories".
 *
 * @property int $id
 * @property string|null $link
 * @property string|null $name
 * @property string|null $update_data
 */
class RepositoriesHelper extends Model
{

    const TOKEN = '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';
    const NAMEGIT = 'gnedasch2011';


    public static function getLastRepo($dataRepoNames, $ajax = false): array
    {

        //если это аякс то удаляем кэш

        $cache = Yii::$app->cache;

        if ($ajax) {
            $cache->delete('repositiries');
        }

        $arrLastRepo = $cache->getOrSet('repositiries', function () use ($dataRepoNames) {

            foreach ($dataRepoNames as $name) {

                $arrLastRepo[] = self::getLastUpdatedRepo(trim($name));

            }

            return $arrLastRepo;
        });

        return $arrLastRepo ?? [];

    }


    static function getDataUser($name)
    {

        $context = stream_context_create([
            'http' => [
                'header' => 'User-Agent: Awesome-Octocat-App\r\n',
                'accept' => 'application/vnd.github.v3+json',
                'login' => self::NAMEGIT,
                'token' => self::TOKEN,
            ]
        ]);

        $url = "https://api.github.com/users/$name";

        try {
            $json = file_get_contents($url, false, $context);
            $dataByUser = json_decode($json, true);

            return $dataByUser;
        } catch (\ErrorException $e) {
            return false;
        }
    }

    static function getAllRepo($url)
    {
        $context = stream_context_create([
            'http' => [
                'header' => 'User-Agent: Awesome-Octocat-App',
                'accept' => 'application/vnd.github.v3+json',
            ]
        ]);

        $json = file_get_contents($url, false, $context);

        $data = json_decode($json, true);

        return $data;
    }

    static function getLastUpdatedRepo($name)
    {

        $userData = self::getDataUser($name);
        if ($userData) {
            $reposUrl = $userData['repos_url'];

            $allRepo = self::getAllRepo($reposUrl);

            $forSort = [];

            foreach ($allRepo as $repo) {
                $forSort[$repo['id']] = $repo['updated_at'];
            }

            arsort($forSort);
            $lastUpdated = current(array_keys($forSort));

            foreach ($allRepo as $rep) {

                if ($rep['id'] == $lastUpdated) {
                    //сохраняем, если новый

                    Repositories::deleteAll(['name' => $name]);

                    $newRep = new Repositories();
                    $data = ['Repositories' => $rep];
                    $newRep->load($data);
                    $newRep->html_url = $data['Repositories']['html_url'];

                    $newRep->owner = serialize($newRep['owner']);
                    $newRep->name = $rep['owner']['login'];

                    if (!$newRep->save()) {
                        // echo '<pre>';print_r($rep->errors);
                    }

                    return $rep;
                }

            }
        }

        return false;

    }
}
