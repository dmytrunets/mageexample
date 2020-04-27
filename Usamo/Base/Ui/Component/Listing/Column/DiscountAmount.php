<?php

namespace Usamo\Base\Ui\Component\Listing\Column;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use \Magento\Sales\Api\OrderRepositoryInterface;
use \Magento\Framework\View\Element\UiComponent\ContextInterface;
use \Magento\Framework\View\Element\UiComponentFactory;
use \Magento\Ui\Component\Listing\Columns\Column;
use \Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Class DiscountAmount
 */
class DiscountAmount extends Column
{
    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceFormatter;

    /**
     * DiscountAmount constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param OrderRepositoryInterface $orderRepository
     * @param PriceCurrencyInterface $priceFormatter
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        OrderRepositoryInterface $orderRepository,
        PriceCurrencyInterface $priceFormatter,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->orderRepository = $orderRepository;
        $this->priceFormatter = $priceFormatter;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $currencyCode = isset($item['base_currency_code']) ? $item['base_currency_code'] : null;
                $order = $this->orderRepository->get($item["entity_id"]);
                $discountAmount = $order->getData("base_discount_amount");

                $item[$this->getData('name')] = $this->priceFormatter->format(
                    $discountAmount,
                    false,
                    null,
                    null,
                    $currencyCode
                );;
            }
        }

        return $dataSource;
    }
}