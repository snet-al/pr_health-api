<?php

namespace App\FCM;

class Push
{
    private $title;

    private $message;

    private $imageUrl;

    private $model;

    private $modelId;

    private $body;

    /**
     * Push constructor.
     *
     * @param $title
     * @param $message
     * @param $imageUrl
     * @param $model
     * @param $modelId
     */
    public function __construct($title, $message, $imageUrl, $model, $modelId)
    {
        $this->title = $title;
        $this->message = $message;
        $this->imageUrl = $imageUrl;
        $this->model = $model;
        $this->modelId = $modelId;
    }

    public function getNotificationMessage()
    {
        return
            [
                'title' => $this->title,
                'body' => $this->message,
            ];
    }

    /**
     * @return array
     */
    public function getNotificationMessageData()
    {
        return [
            'model' => $this->model,
            'model_id' => $this->modelId,
            'image_url' => $this->imageUrl,
        ];
    }
}
