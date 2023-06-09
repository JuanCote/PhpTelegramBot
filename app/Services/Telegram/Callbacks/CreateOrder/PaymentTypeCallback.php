<?php

namespace App\Services\Telegram\Callbacks\CreateOrder;

use App\Models\User;
use App\Services\Dots\DotsService;
use App\Services\Orders\OrdersService;
use App\Services\Telegram\Senders\CreateOrder\CartCheckoutSender;
use App\Services\Users\UsersService;
use Telegram\Bot\Objects\CallbackQuery;


class PaymentTypeCallback
{
    public function __construct(
        UsersService $userService,
        OrdersService $orderService,
    ) {
        $this->userService = $userService;
        $this->orderService = $orderService;
    }
    public function handle(CallbackQuery $callbackQuery)
    {
        $callbackData = $callbackQuery->getData();
        (int)$paymentType = $this->getPaymentTypeFromData($callbackData);
        $chatId = $callbackQuery->message->chat->id;
        $user = $this->userService->findUserByTelegramId($chatId);
        $this->addPaymentTypeToOrder($paymentType, $user);
        app(CartCheckoutSender::class)->handle($callbackQuery->message);
    }

    private function getPaymentTypeFromData(string $callbackData): string
    {
        $array = explode('_', $callbackData);
        return end($array);
    }

    private function addPaymentTypeToOrder(int $paymentType, User $user)
    {
        $this->orderService->updateOrder($user->order, [
            'payment_type' => $paymentType
        ]);
    }
}
