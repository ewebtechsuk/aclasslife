import { Body, Controller, Get, Param, Patch, Post, Put } from '@nestjs/common';
import { IsOptional, IsString } from 'class-validator';

class DealUpdateDto {
  @IsOptional()
  @IsString()
  stage?: string;

  @IsOptional()
  @IsString()
  assignedTo?: string;

  @IsOptional()
  @IsString()
  nextActionAt?: string;
}

class CommissionRuleDto {
  @IsString()
  category!: string;

  @IsString()
  rate!: string;

  @IsOptional()
  @IsString()
  minimumFee?: string;
}

@Controller('admin')
export class AdminController {
  @Patch('listings/:id/approve')
  approveListing(@Param('id') id: string) {
    return { id, status: 'LIVE' };
  }

  @Patch('listings/:id/reject')
  rejectListing(@Param('id') id: string) {
    return { id, status: 'REJECTED' };
  }

  @Patch('vendors/:id/verify')
  verifyVendor(@Param('id') id: string) {
    return { id, verified: true };
  }

  @Get('deals')
  listDeals() {
    return {
      items: [
        { id: 'deal-001', stage: 'QUALIFIED', listing: 'G650' },
        { id: 'deal-002', stage: 'NEGOTIATION', listing: 'Silver Breeze 62m' }
      ]
    };
  }

  @Patch('deals/:id')
  updateDeal(@Param('id') id: string, @Body() body: DealUpdateDto) {
    return {
      id,
      stage: body.stage ?? 'QUALIFIED',
      assignedTo: body.assignedTo ?? null,
      nextActionAt: body.nextActionAt ?? null
    };
  }

  @Get('commission/rules')
  listRules() {
    return {
      items: [
        { id: 'rule-001', category: 'Jets', rate: '3.5%', minimumFee: '75000' }
      ]
    };
  }

  @Post('commission/rules')
  createRule(@Body() body: CommissionRuleDto) {
    return { id: 'rule-002', ...body };
  }

  @Put('commission/rules/:id')
  updateRule(@Param('id') id: string, @Body() body: CommissionRuleDto) {
    return { id, ...body };
  }
}
