import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { InterestPageRoutingModule } from './interest-routing.module';

import { InterestPage } from './interest.page';
import { PipesModule } from '../../../pipes/pipes.module';
@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    InterestPageRoutingModule,
    PipesModule
  ],
  declarations: [InterestPage]
})
export class InterestPageModule {}
