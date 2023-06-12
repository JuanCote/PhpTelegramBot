<?php
/**
 * Description of DotsProvider.php
 * @copyright Copyright (c) MISTER.AM, LLC
 * @author    Egor Gerasimchuk <egor@mister.am>
 */

namespace App\Services\Dots\Providers;


use App\Services\Http\HttpClient;
use Illuminate\Support\Facades\Log;
use function Symfony\Component\ErrorHandler\Tests\testHeader;

class DotsProvider extends HttpClient
{

    protected function getServiceHost()
    {
        return config('services.dots.host');
    }

    public function getCities(): array
    {
        return $this->get('api/v2/cities?v=2.0.0');
    }

    public function getCompanies(string $cityId): array
    {
        return $this->get("api/v2/cities/$cityId/companies?v=2.0.0");

    }
    public function getDishes(string $companyId): array
    {
        return $this->get("api/v2/companies/$companyId/items-by-categories?v=2.0.0");
    }

    public function getDeliveryTypes(string $companyId): array
    {
        return $this->get("api/v2/companies/$companyId/delivery-types?v=2.0.0");
    }
    public function createOrder(array $orderObject): array
    {
        return $this->post('/api/v2/orders?v=2.0.0', $orderObject);
    }
    public function getCompanyInfo(string $companyId): array
    {
        return $this->get("api/v2/companies/$companyId?v=2.0.0");
    }
    public function checkOrder(string $orderId): array
    {
        return $this->get("api/v2/orders/$orderId?v=2.0.0");
    }
    public function resolveCart(array $orderObject): array
    {
        return $this->post('api/v2/cart/prices/resolve?v=2.0.0', $orderObject);
    }
    public function userStatByPhone(int $phoneNumber): array
    {
        return $this->get("api/v2/users/statistics-by-phone?phone=$phoneNumber&v=2.0.0");
    }
    public function UserActiveOrders(string $dotsUserId): array
    {
        return $this->get("api/v2/users/$dotsUserId/orders/active?v=2.0.0");
    }
}
