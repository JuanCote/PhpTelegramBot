<?php

namespace App\Services\Telegram\Callbacks\MainMenu;

use App\Services\Dots\DotsService;
use App\Services\Orders\OrdersService;
use App\Services\Telegram\Senders\MainMenu\HistoryOrdersSender;
use App\Services\Users\UsersService;
use Telegram\Bot\Objects\CallbackQuery;


class HistoryOrdersCallback
{
    public function __construct(
        UsersService $userService,
        DotsService $dotsService,
    ) {
        $this->userService = $userService;
        $this->dotsService = $dotsService;
    }
    public function handle(CallbackQuery $callbackQuery)
    {
        $telegramId = $callbackQuery->message->chat->id;
        $user = $this->userService->findUserByTelegramId($telegramId);
        $historyOrders = $this->dotsService->userHistoryOrders($user->dotsUserId);
        app(HistoryOrdersSender::class)->handle($callbackQuery->message, $historyOrders);
    }
}
