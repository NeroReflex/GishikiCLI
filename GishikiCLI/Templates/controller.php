<?php

use Gishiki\Core\MVC\Controller;
use Gishiki\HttpKernel\Request;
use Gishiki\HttpKernel\Response;
use Gishiki\Algorithms\Collections\SerializableCollection;


final class ctrlName extends Controller
{
    public function getTime()
    {
        $serializableResponse = new SerializableCollection([
            "time" => time()
        ]);
        $this->response->setSerializedBody($serializableResponse);
    }
}