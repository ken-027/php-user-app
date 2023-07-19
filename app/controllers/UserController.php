<?php

namespace Controllers;

use Lib\Response;
use Lib\Upload;
use Lib\Validation;
use Models\User;

class UserController
{
    private $userModel = null;
    public function __construct()
    {
        $this->userModel = new User();
    }

    public function all(): void
    {
        $users = $this->userModel->getAll();

        $this->mapImageToUrl($users);
        Response::json($users);
    }

    public function get(): void
    {
        $user = $this->userModel->getById((int) REQUEST_PARAM[0]);
        $this->mapImageToUrl($user);
        Response::json($user);
    }

    public function destroy(): void
    {
        $user = $this->userModel->getById(REQUEST_PARAM[0]);
        $file = STORAGE . "/" . $user->image;
        if (file_exists($file)) {
            unlink($file);
        }
        $this->userModel->delete(REQUEST_PARAM[0]);

        Response::json(null);
    }

    public function update(): void
    {
        $request = [...$_REQUEST, ...$_FILES];
        $validation = new Validation($request);
        $request = $validation->sanitize();
        $user = $this->userModel->update((object) $request, (int) REQUEST_PARAM[0]);

        (!empty($request->image)) && $user = $this->saveImg($request->image, $user->id);
        $this->mapImageToUrl($user);

        Response::json($user);
    }

    public function store(): void
    {
        $validation = new Validation([...$_REQUEST, ...$_FILES]);
        $validated = $validation->validated();
        $user = $this->userModel->create($validated);

        $user = $this->saveImg($validated->image, $user->id);

        $this->mapImageToUrl($user);
        Response::json($user, 201);
    }

    private function saveImg(array $image, $id)
    {
        $file = new Upload($image);
        $file->store($id);
        return $this->userModel->update((object) ["image" => $image], $id);
    }

    private function mapImageToUrl(array | object &$users)
    {
        if (gettype($users) === 'object') {
            $users->image = HTTP_HOST . "/storage/" . $users->image;
        } else {
            array_walk($users, function (&$val, $key) {
                $val['image'] = HTTP_HOST . "/storage/" . $val['image'];
            });
        }
    }
}
