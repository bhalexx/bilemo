<?php

namespace AppBundle\SerializeEventHandler;

use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;

class PicturePathHandler implements EventSubscriberInterface
{
	private $path;

	public function __construct($path)
	{
		$this->path = $path;
	}

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
    	return [
            ['event' => Events::PRE_SERIALIZE, 'method' => 'onPreSerialize', 'class' => 'AppBundle\Entity\Picture']
        ];
    }

    public function onPreSerialize(PreSerializeEvent $event)
    {
        $picture = $event->getObject();
        
        $picture->setPath($this->path.$picture->getPath());
    }
}