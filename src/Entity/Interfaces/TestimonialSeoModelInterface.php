<?php

declare(strict_types=1);

namespace Manuxi\SuluSharedTools\Entity\Interfaces;

use Manuxi\SuluSharedTools\Entity\TestimonialSeo;
use Symfony\Component\HttpFoundation\Request;

interface TestimonialSeoModelInterface
{
    public function updateTestimonialSeo(TestimonialSeo $testimonialSeo, Request $request): TestimonialSeo;
}
