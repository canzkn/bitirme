import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { ResearchPageRoutingModule } from './research-routing.module';

import { ResearchPage } from './research.page';

import { ComponentsModule } from '../../../components/components.module';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    ResearchPageRoutingModule,
    ComponentsModule
  ],
  declarations: [ResearchPage]
})
export class ResearchPageModule {}
