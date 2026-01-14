import { Controller, Get, Param, Query } from '@nestjs/common';

@Controller('listings')
export class ListingsController {
  @Get()
  list(@Query('category') category?: string) {
    return {
      items: [
        { id: 'listing-1', title: 'Gulfstream G650', category: category ?? 'jets', status: 'LIVE' },
        { id: 'listing-2', title: 'Silver Breeze 62m', category: category ?? 'yachts', status: 'LIVE' }
      ]
    };
  }

  @Get('featured')
  featured() {
    return {
      items: [
        { id: 'listing-1', title: 'Gulfstream G650', category: 'jets' },
        { id: 'listing-2', title: 'Silver Breeze 62m', category: 'yachts' }
      ]
    };
  }

  @Get(':id')
  detail(@Param('id') id: string) {
    return {
      id,
      title: 'Gulfstream G650',
      category: 'jets',
      mode: 'ENQUIRE',
      summary: 'Ultra-long range business jet with bespoke interior.'
    };
  }
}
