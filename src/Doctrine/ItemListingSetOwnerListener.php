<?php
    
namespace App\Doctrine;

use App\Entity\ItemListing;
use Symfony\Component\Security\Core\Security;

class ItemListingSetOwnerListener
{
    /**
     * @var Security
     */
    private $security;
    
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    public function prePersist(ItemListing $itemListing)
    {
        if ($itemListing->getOwner()) {
            return;
        }
    
        if ($this->security->getUser()) {
            $itemListing->setOwner($this->security->getUser());
        }
    }
}