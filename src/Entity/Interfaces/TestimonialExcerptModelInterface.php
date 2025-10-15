<?php

declare(strict_types=1);

namespace Manuxi\SuluSharedTools\Entity\Interfaces;

use Manuxi\SuluSharedTools\Entity\TestimonialExcerpt;
use Symfony\Component\HttpFoundation\Request;

interface TestimonialExcerptModelInterface
{
    public function updateTestimonialExcerpt(TestimonialExcerpt $testimonialExcerpt, Request $request): TestimonialExcerpt;
}
