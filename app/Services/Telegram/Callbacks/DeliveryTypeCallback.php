<?php

namespace App\Services\Telegram\Callbacks;

use App\Models\User;
use App\Services\Dots\DotsService;
use App\Services\Orders\OrdersService;
use App\Services\Telegram\Senders\CategorySender;
use App\Services\Telegram\Senders\CompanyAddressesSender;
use App\Services\Telegram\Senders\DeliveryTypesSender;
use App\Services\Telegram\Senders\DishSender;
use App\Services\Telegram\Senders\PaymentTypeSender;
use App\Services\Users\UsersService;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\CallbackQuery;


class DeliveryTypeCallback
{

    private $userService;
    private $orderService;
    private $dotsService;
    public function __construct(
        UsersService $userService,
        OrdersService $orderService,
        DotsService $dotsService,
    ) {
        $this->userService = $userService;
        $this->orderService = $orderService;
        $this->dotsService = $dotsService;
    }
    public function handle(CallbackQuery $callbackQuery)
    {
        $callbackData = $callbackQuery->getData();
        (int)$deliveryType = $this->getDeliveryTypeFromData($callbackData);
        $user = $this->userService->findUserByTelegramId($callbackQuery->message->chat->id);
        $this->addDeliveryTypeToOrder($deliveryType, $user);
        if (!in_array($deliveryType, [0, 1])){
            app(CompanyAddressesSender::class)->handle($callbackQuery->message, $user);
        }else{
            app(PaymentTypeSender::class)->handle($callbackQuery->message, $user);
        }
    }

    private function getDeliveryTypeFromData(string $callbackData): string
    {
        $array = explode('_', $callbackData);
        return end($array);
    }
    private function addDeliveryTypeToOrder(int $deliveryType, User $user)
    {
        $this->orderService->updateOrder($user->order, [
            'delivery_type' => $deliveryType
        ]);
    }
}
