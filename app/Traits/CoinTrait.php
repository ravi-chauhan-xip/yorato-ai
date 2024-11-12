<?php

namespace App\Traits;

trait CoinTrait
{
    /**
     * @throws \Exception
     */
    public function calculateCoins($amount): float|int
    {
        $packageAmount = $amount;

        return round($packageAmount / coinPrice(), 8);
    }

    /**
     * @throws \Exception
     */
    public function calculateCoinsDollar($amount): float|int
    {
        return round($amount * coinPrice(), 8);
    }

    /**
     * @throws \Exception
     */
    public function calculateCoinsPrice(): ?float
    {
        $price = coinPrice();

        return $price ? $price : null;
    }

    /**
     * @throws \Exception
     */
    public function calculateDollarCoins($amount): ?float
    {
        $price = $this->calculateCoinsPrice();

        return $price ? $amount / $price : null;
    }
}
