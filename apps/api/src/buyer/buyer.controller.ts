import { Body, Controller, Get, Param, Post } from '@nestjs/common';
import { IsOptional, IsString } from 'class-validator';

class EnquiryDto {
  @IsString()
  message!: string;

  @IsOptional()
  @IsString()
  budget?: string;
}

class OfferDto {
  @IsString()
  amount!: string;

  @IsOptional()
  @IsString()
  notes?: string;
}

@Controller()
export class BuyerController {
  @Post('listings/:id/enquire')
  enquire(@Param('id') id: string, @Body() body: EnquiryDto) {
    return {
      dealId: 'deal-001',
      listingId: id,
      status: 'LEAD',
      message: body.message
    };
  }

  @Post('deals/:id/offers')
  offer(@Param('id') id: string, @Body() body: OfferDto) {
    return {
      id: 'offer-001',
      dealId: id,
      amount: body.amount,
      status: 'SUBMITTED'
    };
  }

  @Get('buyer/deals')
  buyerDeals() {
    return {
      items: [
        { id: 'deal-001', listing: 'Silver Breeze 62m', stage: 'QUALIFIED' }
      ]
    };
  }
}
