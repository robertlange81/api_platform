<?php

namespace App\Serializer;

use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class AdminGroupsContextBuilder implements SerializerContextBuilderInterface
{
    private $decorated;
    private $authorizationChecker;
    public function __construct(
        SerializerContextBuilderInterface $decorated,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->decorated = $decorated;
        $this->authorizationChecker = $authorizationChecker;
    }
    
    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
        $isAdmin = $this->authorizationChecker->isGranted('ROLE_ADMIN');
        // $resourceClass = $context['resource_class'] ?? null;
        if ($isAdmin && isset($context['groups'])
        /* && ($resourceClass === User::class  */) {
            $context['groups'][] = 'admin:read';
            $context['groups'][] = 'admin:write';
        }
    
        return $context;
    }
}
