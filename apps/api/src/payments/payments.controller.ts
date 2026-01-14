import { Body, Controller, Post } from '@nestjs/common';

@Controller('payments')
export class PaymentsController {
  @Post('webhook')
  webhook(@Body() payload: Record<string, unknown>) {
    return {
      received: true,
      eventType: payload?.type ?? 'unknown'
    };
  }
}
