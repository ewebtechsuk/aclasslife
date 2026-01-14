import { Body, Controller, Get, Param, Post } from '@nestjs/common';
import { IsString } from 'class-validator';

class MessageDto {
  @IsString()
  content!: string;
}

@Controller('deals')
export class MessagesController {
  @Get(':id/thread')
  thread(@Param('id') id: string) {
    return {
      id: `thread-${id}`,
      participants: ['buyer', 'vendor', 'concierge']
    };
  }

  @Get(':id/messages')
  messages(@Param('id') id: string) {
    return {
      items: [
        { id: 'msg-1', dealId: id, content: 'Welcome to the thread', author: 'concierge' }
      ]
    };
  }

  @Post(':id/messages')
  create(@Param('id') id: string, @Body() body: MessageDto) {
    return {
      id: 'msg-2',
      dealId: id,
      content: body.content,
      author: 'buyer'
    };
  }
}
