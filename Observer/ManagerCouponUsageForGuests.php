<?php

namespace Scriptlodges\GuestCouponManager\Observer;


use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Psr\Log\LoggerInterface;
use Magento\Framework\Message\ManagerInterface;

class ManagerCouponUsageForGuests implements ObserverInterface
{
    protected $orderRepository;
    protected $searchCriteriaBuilder;
    protected $messageManager;
    protected $logger;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ManagerInterface $messageManager,
        LoggerInterface $logger
    ) {
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->messageManager = $messageManager;
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        $quote = $observer->getEvent()->getQuote();
        $couponCode = $quote->getCouponCode();

        // Only apply this restriction for guest users (guest users have no customer ID)
        if ($quote->getCustomerId() == null && $couponCode) {
            $guestEmail = $quote->getCustomerEmail();


            // Check if this guest email has used this coupon before
            $usedCoupon = $this->hasCouponBeenUsedByGuest($guestEmail, $couponCode);

            if ($usedCoupon) {
                // Restrict the coupon and prevent usage
                $quote->setCouponCode('')->collectTotals();
                $this->messageManager->addErrorMessage(__('This coupon code can only be used once by a guest.'));
            }
        }
    }

    /**
     * Check if the guest user (identified by email) has already used the coupon
     *
     * @param string $guestEmail
     * @param string $couponCode
     * @return bool
     */
    private function hasCouponBeenUsedByGuest($guestEmail, $couponCode)
    {
        // Build the search criteria to find orders by guest email and coupon code
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('customer_email', $guestEmail, 'eq')
            ->addFilter('coupon_code', $couponCode, 'eq')
            ->create();

        // Fetch orders based on the search criteria
        $orders = $this->orderRepository->getList($searchCriteria)->getItems();

        // Log the retrieved orders
        $this->logger->info('Orders retrieved for guest email ' . $guestEmail . ' and coupon ' . $couponCode, [
            'orders' => array_map(function ($order) {
                return $order->getData(); // Log all the order data
            }, $orders)
        ]);

        return !empty($orders); // Return true if orders exist, meaning the coupon has been used
    }
}
