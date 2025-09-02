# VanillaMobs  
A plugin that adds creatures to **PocketMine-MP**, inspired by **Minecraft Vanilla mobs**.  
**WARNING: Plugin in development**

## 🌟 Features  
- Adds **custom creatures** that behave like mobs from Minecraft Vanilla.  
- Each entity is designed with its own **unique AI (Artificial Intelligence)**.  
- Provides **individual intelligence per mob**, allowing more natural and diverse behaviors.  
- Supports **custom animations, sounds, and attributes**.  
- Designed to be **lightweight and optimized** for PocketMine-MP servers.  

## 🧠 Unique Pathfinding System  
VanillaMobs does not use traditional A* or complex pathfinding algorithms.  
Instead, it introduces a **player-movement-based pathfinding system**:  
- Mobs **learn and copy player movements** to find their way.  
- By observing and recording player motion, mobs replay similar paths to navigate the terrain.  
- This makes AI **lightweight** yet still provides realistic navigation in PocketMine-MP.  

### Example  
If a player moves around obstacles (trees, walls), VanillaMobs records that motion pattern.  
The mob can then "copy" that movement to find its own way around obstacles.  

**Benefits**:  
- ✅ Lower CPU usage compared to traditional pathfinding  
- ✅ Natural-looking mob movement  
- ✅ Allows customization per mob type  

## 📌 Roadmap  
✅ Basic hostile and passive mobs  
✅ Movement and pathfinding system  
🔲 Advanced combat mechanics  
🔲 Breeding and interaction system  

## 🤝 Contribution  
We welcome contributions! You can:  
- Suggest new mob features  
- Report bugs via Issues  
- Submit Pull Requests for improvements  
