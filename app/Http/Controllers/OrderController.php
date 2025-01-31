<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Http\Services\OrderService;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\OrderResource;
use App\Http\Requests\StoreOrderRequest;
//use App\Http\Requests\UpdateOrderRequest;


class OrderController extends BasicController
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService,OrderRepository $orderRepository)
    {
        $this->orderService = $orderService;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Получение списка всех заказов с информацией о пользователе и товарах.
     */
    public function index()
    {
        try {
            $orders =   $this->orderRepository->getAllOrders();
            return $this->successResponse(
                OrderResource::collection($orders)
            );

        } catch (\Throwable $e) {
            return $this->errorResponse(
                $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Получение деталей конкретного заказа.
     *
     */
    public function show(int $id)
    {
        try {
            $order = $this->orderRepository->findOrFail($id);
            return $this->successResponse(
                new OrderResource($order)
            );
        }
        catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                'Order not found',
                Response::HTTP_NOT_FOUND
            );
        } catch (\Throwable $e) {
            return $this->errorResponse(
                $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Создание нового заказа.
     * Необходимые параметры: user_id и список товаров с их количеством.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreOrderRequest  $request)
    {
        try {
            $order = $this->orderRepository->createOrder(
                $request->validated()
            );

            return $this->successResponse(
                new OrderResource($order),
                Response::HTTP_CREATED
            );
        } catch (\Throwable $e) {
            return $this->errorResponse(
                'Order creation failed',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }

    /**
     * Обновление статуса заказа.
     * Необходимый параметр: status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(\App\Http\Requests\UpdateOrderRequest  $request, $id)
    {
        try {
            $order = $this->orderRepository->updateOrderStatus(
                $id,
                $request->validated('status')
            );
            return $this->successResponse(
                new OrderResource($order)
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                'Order not found',
                Response::HTTP_NOT_FOUND
            );
        } catch (\Throwable $e) {
            return $this->errorResponse(
                'Order update failed',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Удаление заказа.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $this->orderRepository->deleteOrder($id);
            return $this->successResponse(
                null,
                Response::HTTP_NO_CONTENT
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                'Order not found',
                Response::HTTP_NOT_FOUND
            );
        } catch (\Throwable $e) {
            return $this->errorResponse(
                'Order deletion failed',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
