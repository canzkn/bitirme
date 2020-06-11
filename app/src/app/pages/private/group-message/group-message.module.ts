import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { GroupMessagePageRoutingModule } from './group-message-routing.module';

import { GroupMessagePage } from './group-message.page';
import { SocketIoModule, SocketIoConfig } from 'ngx-socket-io';
const config: SocketIoConfig = { url: 'http://localhost:3001', options: {} };

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    GroupMessagePageRoutingModule,
    SocketIoModule.forRoot(config)
  ],
  declarations: [GroupMessagePage]
})
export class GroupMessagePageModule {}
