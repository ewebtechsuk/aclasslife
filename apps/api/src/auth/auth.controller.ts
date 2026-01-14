import { Body, Controller, Get, Post } from '@nestjs/common';
import { IsEmail, IsEnum, IsOptional, IsString, MinLength } from 'class-validator';

enum Role {
  ADMIN = 'ADMIN',
  STAFF = 'STAFF',
  VENDOR = 'VENDOR',
  BUYER = 'BUYER'
}

class RegisterDto {
  @IsEmail()
  email!: string;

  @IsString()
  @MinLength(8)
  password!: string;

  @IsEnum(Role)
  role!: Role;

  @IsOptional()
  @IsString()
  fullName?: string;
}

class LoginDto {
  @IsEmail()
  email!: string;

  @IsString()
  password!: string;
}

@Controller('auth')
export class AuthController {
  @Post('register')
  register(@Body() body: RegisterDto) {
    return {
      user: {
        id: 'usr_001',
        email: body.email,
        role: body.role,
        fullName: body.fullName ?? null
      },
      accessToken: 'placeholder-token'
    };
  }

  @Post('login')
  login(@Body() body: LoginDto) {
    return {
      user: {
        id: 'usr_001',
        email: body.email,
        role: 'BUYER'
      },
      accessToken: 'placeholder-token'
    };
  }

  @Get('me')
  me() {
    return {
      id: 'usr_001',
      email: 'buyer@aclasslife.com',
      role: 'BUYER'
    };
  }
}
