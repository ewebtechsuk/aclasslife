import { Module } from '@nestjs/common';
import { AuthController } from './auth/auth.controller';
import { ListingsController } from './listings/listings.controller';
import { VendorController } from './vendor/vendor.controller';
import { BuyerController } from './buyer/buyer.controller';
import { DealsController } from './deals/deals.controller';
import { MessagesController } from './messages/messages.controller';
import { AdminController } from './admin/admin.controller';
import { PaymentsController } from './payments/payments.controller';

@Module({
  imports: [],
  controllers: [
    AuthController,
    ListingsController,
    VendorController,
    BuyerController,
    DealsController,
    MessagesController,
    AdminController,
    PaymentsController
  ]
})
export class AppModule {}
