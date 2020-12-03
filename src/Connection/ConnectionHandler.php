<?php

namespace Livewire\Connection;

use Illuminate\Validation\ValidationException;
use Livewire\ResponsePayload;

abstract class ConnectionHandler
{
    public function handle($payload)
    {
        $instance = ComponentHydrator::hydrate($payload['name'], $payload['id'], $payload['data'], $payload['checksum']);

        $instance->setPreviouslyRenderedChildren($payload['children']);
        $instance->hashPropertiesForDirtyDetection();

        try {
            foreach ($payload['actionQueue'] as $action) {
                $this->processMessage($action['type'], $action['payload'], $instance);
            }
        } catch (ValidationException $e) {
            $errors = $e->validator->errors();
        }

        $dom = $instance->output(isset($errors) ? $errors : null);
        $data = ComponentHydrator::dehydrate($instance);
        $events = $instance->getEventsBeingListenedFor();
        $eventQueue = $instance->getEventQueue();

        return new ResponsePayload([
            'id' => $payload['id'],
            'dom' => $dom,
            'dirtyInputs' => $instance->getDirtyProperties(),
            'children' => $instance->getRenderedChildren(),
            'eventQueue' => $eventQueue,
            'events' => $events,
            'data' => $data,
            'redirectTo' => isset($instance->redirectTo) ? $instance->redirectTo : false,
        ]);
    }

    public function processMessage($type, $data, $instance)
    {
        $instance->updating();

        switch ($type) {
            case 'syncInput':
                $instance->syncInput($data['name'], $data['value']);
                break;
            case 'callMethod':
                $instance->callMethod($data['method'], $data['params']);
                break;
            case 'fireEvent':
                $instance->fireEvent($data['event'], $data['params']);
                break;
            default:
                throw new \Exception('Unrecongnized message type: ' . $type);
                break;
        }

        $instance->updated();
    }
}
