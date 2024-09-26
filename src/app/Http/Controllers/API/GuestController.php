<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\StoreGuestRequest;
use App\Http\Requests\UpdateGuestRequest;
use App\Http\Resources\GuestResource;
use App\Interfaces\GuestRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Exception;

class GuestController extends BaseController
{
    public function __construct(
        private GuestRepositoryInterface $guestRepositoryInterface
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $guests = $this->guestRepositoryInterface->list();
        } catch (Exception $e) {
            return $this->sendError('Failed to get a guest list', $e, 500);
        }

        return $this->sendResponse(GuestResource::collection($guests));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGuestRequest $request): JsonResponse
    {
        try {
            $guest = $this->guestRepositoryInterface->store($request->validated());
        } catch (Exception $e) {
            return $this->sendError('Failed to create guest', $e, 500);
        }

        return $this->sendResponse(new GuestResource($guest), 'Guest Create Successful', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $guest = $this->guestRepositoryInterface->getById($id);
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Guest Not Found');
        }

        return $this->sendResponse(new GuestResource($guest));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGuestRequest $request, $id): JsonResponse
    {
        try {
            $this->guestRepositoryInterface->update($request->validated(), $id);
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Guest Not Found');
        } catch (Exception $e) {
            return $this->sendError('', $e, 500);
        }

        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->guestRepositoryInterface->delete($id);
        } catch (Exception $e) {
            return $this->sendError('Failed do delete a guest', $e, 500);
        }

        return $this->sendResponse(status: 204);
    }
}
