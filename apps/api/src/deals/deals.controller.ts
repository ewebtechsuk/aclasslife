import { Controller, Get, Param } from '@nestjs/common';

@Controller('deals')
export class DealsController {
  @Get(':id')
  detail(@Param('id') id: string) {
    return {
      id,
      listing: 'Gulfstream G650',
      stage: 'NEGOTIATION',
      threadId: 'thread-001'
    };
  }
}
