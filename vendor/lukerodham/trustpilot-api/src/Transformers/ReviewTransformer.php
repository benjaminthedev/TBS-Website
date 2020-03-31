<?php

namespace LukeRodham\TrustPilot\Transformers;

class ReviewTransformer
{
    /**
     * @var
     */
    private $title;
    /**
     * @var
     */
    private $review;
    /**
     * @var
     */
    private $companyReply;
    /**
     * @var
     */
    private $companyReplyCreatedAt;
    /**
     * @var
     */
    private $createdAt;
    /**
     * @var
     */
    private $reviewLink;
    /**
     * @var
     */
    private $rating;

    /**
     * @var
     */
    private $author;

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * @return mixed
     */
    public function getCompanyReply()
    {
        return $this->companyReply;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getReviewLink()
    {
        return $this->reviewLink;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return mixed
     */
    public function getCompanyReplyCreatedAt()
    {
        return $this->companyReplyCreatedAt;
    }

    /**
     * @param array $reviews
     *
     * @return array
     */
    public function transformArray(array $reviews)
    {
        $transformedData = [];

        foreach ($reviews['reviews'] as $review) {
            $transformedData[] = $this->transform($review);
        }

        return $transformedData;
    }

    /**
     * @param $review
     *
     * @return ReviewTransformer
     */
    public function transform($review)
    {
        $reviewObj                        = new self;
        $reviewObj->title                 = $review['title'];
        $reviewObj->review                = $review['text'];
        $reviewObj->companyReply          = $review['companyReply']['text'];
        $reviewObj->companyReplyCreatedAt = $review['companyReply']['createdAt'];
        $reviewObj->rating                = $review['stars'];
        $reviewObj->createdAt             = $review['createdAt'];
        $reviewObj->author                = trim($review['consumer']['displayName']);

        return $reviewObj;
    }
}